import React from 'react';
import {observer} from 'mobx-react-lite';
import {Spinner} from 'react-bootstrap';
import ReactDOM from 'react-dom';
import propertyEditorStateInstance from './state';
import PropertyTable from './components/property-table';
import PropertyEditorCreatePropertyModal from './components/create-property-modal';


const PropertyEditor = observer(() => {
  return <div className="row">
    <div className="col-lg-12">
      <div className="card m-b-30">
        <div className="card-header">
          <div className="row align-items-center">
            <div className="col-6">
              <h5 className="card-title mb-0">Свойства</h5>
            </div>
          </div>
        </div>

        <div className="card-body">
          <div className="text-right m-b-15">
            <PropertyEditorCreatePropertyModal />
          </div>
          <hr/>
          <div>
            { propertyEditorStateInstance.isAwait
              ? <div className="text-center"><Spinner animation={ 'border' } /></div>
              : <PropertyTable dataSet={ propertyEditorStateInstance.dataSet } /> }
          </div>

        </div>
      </div>
    </div>
  </div>
});

const renderPropertyEditor = () => {
  const root = document.getElementById('property-editor-root');
  if (root !== null) {
    propertyEditorStateInstance.init();
    ReactDOM.render(<PropertyEditor />, root);
  }
};

export default renderPropertyEditor;
