import React from 'react';
import CreateProductCategoryModal from '../../../common/product-category/create-product-category-modal';
import productCategoryEditorStateInstance from '../../state';


const ProductCategoryEditorCreateProductCategoryModal = ({
  productCategoryDict
}) => {

  return <CreateProductCategoryModal productCategoryDict={ productCategoryDict }
                                     btnClasses={ 'btn btn-outline-info' }
                                     onSuccess={ () => productCategoryEditorStateInstance.reloadProductCategories() }
  />;
};

export default ProductCategoryEditorCreateProductCategoryModal;
