import React from 'react';
import CreateProductPageModal from '../../../../../common/product-page/create-product-page-modal';
import productPageEditorStateInstance from '../../state';


const ProductPageEditorCreateProductPageModal = ({
  productId
}) => {
  return <CreateProductPageModal productId={productId}
                                 btnClasses={ 'btn btn-outline-info' }
                                 onSuccess={ () => productPageEditorStateInstance.reloadPageData() } />;
};

export default ProductPageEditorCreateProductPageModal;
