import React from 'react';
import CreateProductModal from '../../../product-card/components/create-product-modal';
import productListStateInstance from '../../state';
import productListFilterStateInstance from '../filter/state';


const CreateProductModalForProductList = () => {
  return <CreateProductModal getVendors={ () => productListStateInstance.vendors }
                             btnClasses={ 'btn btn-outline-info' }
                             onSuccess={ () => productListStateInstance.reloadProducts() }
                             productCategoryId={ productListFilterStateInstance.categoryId ?? null }

  />;
};

export default CreateProductModalForProductList;
