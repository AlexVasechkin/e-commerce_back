import React from 'react';
import CreateCategoryPageModal from '../../../common/category-page/create-category-page-modal';
import productCategoryEditorStateInstance from '../../state';


const ProductCategoryEditorCreateCategoryPageModal = ({ categoryId }) => {

  const triggerComponent = show => <button
    className={ 'btn btn-outline-light' }
    onClick={ show }
  >Создать</button>;

  return <CreateCategoryPageModal categoryId={ categoryId }
                                  triggerComponent={ triggerComponent }
                                  onSuccess={ () => productCategoryEditorStateInstance.reloadProductCategories() }
  />;
};

export default ProductCategoryEditorCreateCategoryPageModal;
