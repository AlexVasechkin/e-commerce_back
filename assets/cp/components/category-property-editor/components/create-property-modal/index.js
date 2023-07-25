import React from 'react';
import CreatePropertyModal from '../../../common/property/create-property-modal';
import categoryPropertyEditorStateInstance from '../../state';


const CategoryPropertyEditorCreatePropertyModal = () => {
  return <CreatePropertyModal btnClasses={'btn btn-outline-success'}
                              onSuccess={ () => categoryPropertyEditorStateInstance.reloadProperties() }
                              buttonCaption={ 'Добавить свойство' }
  />;
};

export default CategoryPropertyEditorCreatePropertyModal;
