import React from 'react';
import CreateCategoryPropertyModal from '../../../common/category-property/create-category-property-modal';
import categoryPropertyEditorStateInstance from '../../state';


const CategoryPropertyEditorCreateModal = ({
  properties
}) => {
  return <CreateCategoryPropertyModal categories={ categoryPropertyEditorStateInstance.categories }
                                      properties={ properties }
                                      onSuccess={ () => categoryPropertyEditorStateInstance.reloadDataSet() }
                                      btnClasses={ 'btn btn-outline-success' }
  />
};

export default CategoryPropertyEditorCreateModal;
