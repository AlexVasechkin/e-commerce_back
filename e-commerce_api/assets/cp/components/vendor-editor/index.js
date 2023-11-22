import React from 'react';
import {observer} from 'mobx-react-lite';
import ReactDOM from 'react-dom';
import VendorEditorCreateVendorModal from './components/create-vendor-modal';
import VendorTable from './components/vendor-table';
import vendorEditorStateInstance from './state';
import {Spinner} from 'react-bootstrap';


const VendorEditor = observer(() => {

  return <div className="row">
    <div className="col-lg-12">
      <div className="card m-b-30">
        <div className="card-header">
          <div className="row align-items-center">
            <div className="col-6">
              <h5 className="card-title mb-0">Производители</h5>
            </div>
          </div>
        </div>

        <div className="card-body">
          <div className="text-right m-b-15">
            <VendorEditorCreateVendorModal />
          </div>
          <hr/>
          <div>
            { vendorEditorStateInstance.isAwait ? <div className="text-center"><Spinner animation={ 'border' } /></div> : <VendorTable dataSet={ vendorEditorStateInstance.dataSet } /> }
          </div>

        </div>
      </div>
    </div>
  </div>
});

const renderVendorEditor = () => {
  const root = document.getElementById('vendor-editor-root');
  if (root !== null) {
    vendorEditorStateInstance.init();
    ReactDOM.render(<VendorEditor />, root);
  }
};

export default renderVendorEditor;
