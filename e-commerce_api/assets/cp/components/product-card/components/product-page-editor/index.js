import React from 'react';
import {observer} from 'mobx-react-lite';
import productPageEditorStateInstance from './state';
import {Spinner} from 'react-bootstrap';
import ProductPageEditorUpdateProductPageForm from './components/update-product-page-form';
import ProductPageEditorCreateProductPageModal from './components/create-product-page-modal';


const ProductPageEditor = observer(() => {

  if (productPageEditorStateInstance.productId === null) {
    return null;
  }

  const getContent = () => productPageEditorStateInstance.dataSet === null
    ? <ProductPageEditorCreateProductPageModal productId={ productPageEditorStateInstance.productId } />
    : <ProductPageEditorUpdateProductPageForm />
  ;

  return  productPageEditorStateInstance.isAwait
    ? <Spinner animation={ 'border' } />
    : getContent()
});

export default ProductPageEditor;
