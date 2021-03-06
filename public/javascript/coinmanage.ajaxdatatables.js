/**
 * SURFconext Manage
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext Manage
 * @package
 * @copyright Copyright © 2010-2011 SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

var COINMANAGE = {
};

COINMANAGE.AjaxDataTable = function(selector) {
    var _dataSourceUrl, // required
        _dataSourceFields,  // required
        _displayColumns,  // required
        _limit,
        _sortedField,
        _sortedDir = 'asc',
        _searchFormId,
        _onRecordClick,
        _recordActions = [],
        _node;

    if (!selector) {
        throw "No selector for DataTable";
    }

    _node = YAHOO.util.Selector.query(selector, undefined, true);
    if (!_node) {
        throw "Unable to find node in DOM for selector: " + selector;
    }

    function _validate() {
        if (!_dataSourceUrl) {
            throw "No DataSource URL set for datatable: " + selector;
        }

        if (!_dataSourceFields) {
            throw "No DataSource fields set for datatable: " + selector;
        }

        if (!_displayColumns || _displayColumns.length === 0) {
            throw "No columns to display for datatable: " + selector;
        }
    }

    return {
        setLoadUrl: function (url) {
            _dataSourceUrl = url;
            return this;
        },

        setLoadFields: function (columns) {
            _dataSourceFields = columns;
            return this;
        },

        setDisplayColumns: function (columns) {
            _displayColumns = columns;
            return this;
        },

        onRecordClick: function (callback) {
            _onRecordClick = callback;
            return this;
        },

        setLimit: function(limit) {
            _limit = limit;
            return this;
        },

        /**
         * Add a record action.
         *
         * Available options:
         * - imgAlt
         * - imgSrc
         * - onClick
         * - href
         * - title
         * @param options
         */
        addRecordAction: function(name, options) {
            options.name = name;
            _recordActions[_recordActions.length] = options;
            return this;
        },

        setSortedBy: function(field) {
            _sortedField = field;
            return this;
        },

        setSortedDir: function(dir) {
            _sortedDir = dir;
            return this;
        },

        registerSearchForm: function(id) {
            _searchFormId = id;
            return this;
        },

        render: function(gridId) {
            var DataTable;

            _validate();

            this.DataSource = new YAHOO.util.XHRDataSource(_dataSourceUrl);
            this.DataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
            this.DataSource.connXhrMode = "queueRequests";
            this.DataSource.responseSchema = {
                resultsList: "ResultSet",
                fields: _dataSourceFields,
                metaFields: {
                    startIndex: "startIndex",
                    totalRecords: "totalRecords" // Access to value in the server response
                }
            };

            var searchQuery = "";
            if (_searchFormId) {
                var searchFormEl = YAHOO.util.Dom.get(_searchFormId);
                var formInputs = searchFormEl.getElementsByTagName('input');
                YAHOO.util.Event.addListener(searchFormEl, "submit", function(e) {
                    var key, value;

                    searchQuery = "";
                    for (var i=0; i < formInputs.length; i++) {
                        if (formInputs[i].type !== 'text') {
                            continue;
                        }

                        value = formInputs[i].value;
                        for (var j=0; j < formInputs[i].attributes.length; j++) {
                            if (formInputs[i].attributes[j].name==='data-key') {
                                key = formInputs[i].attributes[j].value
                                break;
                            }
                        }
                        searchQuery += "&search[" + key + ']=' + encodeURIComponent(value);
                    }

                    DataTable.reQuery();
                });
            }

            // Customize request sent to server to be able to set total # of records
            var generateRequest = function(oState, oSelf) {
                // Get states or use defaults
                oState = oState || { 
                    pagination: null, 
                    sortedBy: {
                        key: _sortedField, 
                        dir: _sortedDir === "desc" ? YAHOO.widget.DataTable.CLASS_DESC : YAHOO.widget.DataTable.CLASS_ASC  
                    } 
                };
                var sort        = (oState.sortedBy) ? oState.sortedBy.key : _sortedField;
                var dir         = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc";
                var startIndex  = (oState.pagination) ? oState.pagination.recordOffset : 0;
                var results     = (oState.pagination) ? oState.pagination.rowsPerPage : _limit;

                // Build custom request
                return  "sort=" + sort +
                        "&dir=" + dir +
                        "&startIndex=" + startIndex +
                        "&results=" + results + searchQuery +
                        "&gridid=" + gridId;
            };

            // DataTable configuration
            var Configs = {
                generateRequest: generateRequest,
                initialRequest: generateRequest(), // Initial request for first page of data
                dynamicData: true // Enables dynamic server-driven data
            };
            if (_sortedField) {
                Configs.sortedBy = {
                    key: _sortedField
                };
                if (_sortedDir && _sortedDir==='desc') {
                    Configs.sortedBy.dir = YAHOO.widget.DataTable.CLASS_DESC;
                }
                else if (_sortedDir && _sortedDir==='asc') {
                    Configs.sortedBy.dir = YAHOO.widget.DataTable.CLASS_ASC;
                }
            }
            if (_limit) {
                Configs.paginator = new YAHOO.widget.Paginator({
                    rowsPerPage: _limit
                });
            }

            if (_recordActions && _recordActions.length > 0) {
                _displayColumns[_displayColumns.length] = {
                    label: "",
                    key: 'action',
                    formatter: function(el, record) {
                        var recordData = record.getData();
                        for (var i=0; i < _recordActions.length; i++) {
                            (function() {
                                var root, parent, recordAction;
                                
                                // Add a spacer
                                el.appendChild(document.createElement('span')).innerHTML = '&nbsp;&nbsp;&nbsp;';

                                // Create a root span
                                root = el.appendChild(document.createElement('span'));
                                root.className = 'record-action';
                                parent = root;

                                recordAction = _recordActions[i];
                                if ("onClick" in recordAction || "href" in recordAction) {
                                    parent = parent.appendChild(document.createElement('a'));
                                    if ('href' in recordAction) {
                                        parent.href = recordAction.href;
                                        for (var key in recordData) {
                                            if (recordData.hasOwnProperty(key)) {
                                                parent.href = parent.href.replace(
                                                        '__' + key + '__', recordData[key]
                                                );
                                            }
                                        }
                                    }
                                    else {
                                        parent.href = '#';
                                    }

                                    if ('onClick' in recordAction) {
                                        YAHOO.util.Event.addListener(parent, "click", function(e) {
                                            YAHOO.util.Event.stopPropagation(e);
                                            return recordAction.onClick(el, record, DataTable);
                                        });
                                    }

                                    if ('title' in recordAction) {
                                        parent.title = recordAction.title;
                                    }
                                }
                                if (recordAction.imgSrc) {
                                    parent = parent.appendChild(document.createElement('img'));
                                    parent.src = recordAction.imgSrc;
                                    if ('imgAlt' in recordAction) {
                                        parent.alt = recordAction.imgAlt;
                                    }
                                    if ('title' in recordAction) {
                                        parent.title = recordAction.title;
                                    }
                                }
                                el.appendChild(root);
                            })();
                        }
                    }
                };
            }

            DataTable = new YAHOO.widget.DataTable(_node, _displayColumns, this.DataSource, Configs);

            DataTable.doBeforeLoadData = function(oRequest, oResponse, oPayload) {
                oPayload.totalRecords = oResponse.meta.totalRecords;
                if (_limit) {
                    oPayload.pagination.recordOffset = oResponse.meta.startIndex;
                }
                return oPayload;
            };

            if (_onRecordClick) {
                DataTable.subscribe("rowMouseoverEvent", function() {
                    DataTable.onEventHighlightRow.apply(DataTable, arguments);
                });
                DataTable.subscribe("rowMouseoutEvent", function() {
                    DataTable.onEventUnhighlightRow.apply(DataTable, arguments);
                });

                DataTable.subscribe("rowClickEvent", function(e) {
                    DataTable.onEventSelectRow.apply(DataTable, arguments);

                    if (e.target.nodeName === "IMG" || e.target.nodeName === "A") {
                        // Ignore clicks on record actions or URLs
                        return false;
                    }

                    _onRecordClick(DataTable);

                    return false;
                });
            }

            return DataTable;
        }
    };
};
