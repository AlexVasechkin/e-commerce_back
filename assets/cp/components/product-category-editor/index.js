import React from 'react';
import ReactDOM from 'react-dom';
import productCategoryEditorStateInstance from './state';
import { observer } from 'mobx-react-lite';
import ProductCategoryEditorCreateProductCategoryModal from './components/create-product-category-modal';
import {Spinner} from 'react-bootstrap';
import ProductCategoryTable from './components/product-category-table';


const ProductCategoryEditor = observer(() => {

  return <div className="row">
    <div className="col-lg-12">
      <div className="card m-b-30">
        <div className="card-header">
          <div className="row align-items-center">
            <div className="col-6">
              <h5 className="card-title mb-0">Категории товаров</h5>
            </div>
          </div>
        </div>

        <div className="card-body">
          <div className="text-right m-b-15">
            <ProductCategoryEditorCreateProductCategoryModal productCategoryDict={ productCategoryEditorStateInstance.productCategoryDict } />
          </div>
          <hr/>
          <div>
            { productCategoryEditorStateInstance.isAwait
              ? <div className="text-center"><Spinner animation={ 'border' } /></div>
              : <ProductCategoryTable dataSet={ productCategoryEditorStateInstance.productCategories }
                                      productCategoryDict={ productCategoryEditorStateInstance.productCategoryDict }
                />
            }
          </div>

        </div>
      </div>
    </div>
  </div>;
});

const renderProductCategoryEditor = () => {
  const root = document.getElementById('product__category__editor__root');
  if (root) {
    productCategoryEditorStateInstance.init();
    ReactDOM.render(<ProductCategoryEditor />, root);
  }
};

export default renderProductCategoryEditor;
