import React from 'react';
import CreatePropertyModal from '../../../common/property/create-property-modal';
import propertyEditorStateInstance from '../../state';


const PropertyEditorCreatePropertyModal = () => {
  return <CreatePropertyModal btnClasses={ 'btn btn-outline-success' }
                              onSuccess={ () => propertyEditorStateInstance.reloadDataSet() }
  />
};

export default PropertyEditorCreatePropertyModal;
