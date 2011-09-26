<?php

class EngineBlock_LoginOverviewController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                             ->addActionContext('show-by-type', 'json')
                             ->addActionContext('show-by-idp', 'json')
                             ->addActionContext('show-by-sp', 'json')
                             ->initContext();
    }

    public function showByTypeAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);

        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchCountByType($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }

    public function showByIdpAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);

        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchCountByIdp($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }

    public function showBySpAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);

        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchCountBySp($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }

    /**
     * Show SP logins for one IDP.
     *
     * @see BACKLOG-20
     */
    public function showSpLoginsByIdpAction()
    {
        $entityId = $this->getRequest()->getParam('eid', false);

        if (!$entityId) {
            throw new Exception('No entity ID provided!');
        }
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir)
                ->addSearchParam('entity_id', $entityId);

        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchSpLoginsByIdp($params);
        $this->view->entityId  = $entityId;
        $this->view->ResultSet = $results->getResults();
    }

    /**
     * Show IdP logins for one SP.
     *
     * @see BACKLOG-21
     */
    public function showIdpLoginsBySpAction()
    {
        $entityId = $this->getRequest()->getParam('eid', false);

        if (!$entityId) {
            throw new Exception('No entity ID provided!');
        }
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir)
                ->addSearchParam('entity_id', $entityId);

        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchIdpLoginsBySp($params);
        $this->view->entityId  = $entityId;
        $this->view->ResultSet = $results->getResults();
    }
}