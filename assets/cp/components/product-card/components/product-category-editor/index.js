import React from 'react';
import Select from 'react-select';
import productCategoryEditorStateInstance from './state';
import {observer} from 'mobx-react-lite';


const ProductCardProductCategoryEditor = observer(({
  options
}) => {
  return <Select options={ options }
                 placeholder={ 'Категория' }
                 value={ options.find(el => el.value == productCategoryEditorStateInstance.productCategoryId) }
                 onChange={ e => productCategoryEditorStateInstance.setProductCategoryId(e ? e.value : e) }
                 isDisabled={   productCategoryEditorStateInstance.isAwait
                             || productCategoryEditorStateInstance.productId === null
                 }
                 isClearable
         />;
});

export default ProductCardProductCategoryEditor;
