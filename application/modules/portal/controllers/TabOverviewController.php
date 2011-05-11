<?php

class Portal_TabOverviewController extends Zend_Controller_Action
{
    public function init()
        {
            $this->view->identity = $this->_helper->Authenticate();

            $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                 ->addActionContext('show-by-team', 'json')
                                 ->initContext();
        }

        public function showByTeamAction()
        {
            if ($this->getRequest()->getParam('download', false)) {
                $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
            }

            $gadgetList = new Model_GadgetList();
            $inputFilter = $this->_helper->FilterLoader();

            $results = $gadgetList->getTeamTabs(
                $inputFilter->sort,
                $inputFilter->dir,
                $inputFilter->results,
                $inputFilter->startIndex
            );
            $resultTotal = $gadgetList->getTeamTabs(
                null,
                null,
                null,
                0,
                true
            );

            $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
            $this->view->ResultSet          = $results;
            $this->view->recordsReturned    = count($results);
            $this->view->totalRecords       = $resultTotal;
        }
}


