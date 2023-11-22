import React, { useEffect } from 'react';
import { observer } from 'mobx-react-lite';
import productListStateInstance from './state';
import ReactDOM from 'react-dom';
import CreateProductModalForProductList from './components/create-product-modal';
import {Spinner} from 'react-bootstrap';
import ImageSlider from '../common/image-slider';
import {UrlParser} from 'url-params-parser';
import PageNavigator from '../common/page-navigator';
import ProductListFilter from './components/filter';


const ProductList = observer(() => {

  useEffect(() => {
    const {
      page = 1
    } = UrlParser(document.location.href).queryParams;

    productListStateInstance.currentPage = parseInt(page);

  }, []);

  const Navigator = () => <PageNavigator onFirstPageClick={ () => productListStateInstance.currentPage = 1 }
                                         onPrevPageClick={ () => productListStateInstance.currentPage = productListStateInstance.currentPage > 1 ? productListStateInstance.currentPage - 1 : 1 }
                                         onNextPageClick={ () => productListStateInstance.currentPage = productListStateInstance.currentPage < productListStateInstance.totalPageCount ? productListStateInstance.currentPage + 1 : productListStateInstance.totalPageCount }
                                         onLastPageClick={ () => productListStateInstance.currentPage = productListStateInstance.totalPageCount }
                                         firstPageDisabled={ (productListStateInstance.currentPage === null) || (productListStateInstance.currentPage === 1) }
                                         prevDisabled={ (productListStateInstance.currentPage === null) || (productListStateInstance.currentPage === 1) }
                                         nextDisabled={ (productListStateInstance.currentPage === null) || (productListStateInstance.currentPage === productListStateInstance.totalPageCount) }
                                         lastPageDisabled={ (productListStateInstance.currentPage === null) || (productListStateInstance.currentPage === productListStateInstance.totalPageCount) }
                                         currentPage={ productListStateInstance.currentPage }
                                         totalPageCount={ productListStateInstance.totalPageCount }
  />;

  const submitSearch = e => {
    e.preventDefault();
    productListStateInstance.currentPage = 1;
    productListStateInstance.reloadProducts();
  };

  return <div className="row">
    <div className="col-12">
      <div className="card m-b-30">
        <div className="card-body">
          <CreateProductModalForProductList />
        </div>
      </div>
    </div>

    <div className="col-12 col-md-3">
      <div className="card m-b-30">
        <div className="card-header">
          <div className="row align-items-center">
            <div className="col-6">
              <h5 className="card-title mb-0">Фильтры</h5>
            </div>
          </div>
        </div>

        <div className="card-body">
          <ProductListFilter productCategories={ productListStateInstance.productCategories }
                             vendors={ productListStateInstance.vendors }
                             productGroups={ productListStateInstance.productGroups }
                             disabled={ productListStateInstance.isAwait }
          />
        </div>
      </div>
    </div>

    <div className="col-12 col-md-9">
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-body">
              <div style={{ display: 'flex', justifyContent: 'center' }}>
                <div>
                  <Navigator />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="row">
        <div className="col-12">
          <div className="card m-t-30">
            <div className="card-body">
              <form onSubmit={ submitSearch } >
                <div className="input-group" >
                  <input className="form-control"
                         type="text"
                         placeholder="поиск"
                         value={ productListStateInstance.searchString }
                         onChange={ e => productListStateInstance.searchString = e.target.value }
                  />

                  <div className="input-group-append">
                    <button type="submit" className="btn btn-outline-primary">Найти</button>
                  </div>

                </div>

              </form>
            </div>
          </div>
        </div>
      </div>

      <div className="row">
        {   productListStateInstance.isAwait
          ? <div className="col-12">
            <div className="card m-t-30">
              <div className="card-body">
                <div className="text-center">
                  <Spinner animation={'border'} />
                </div>
              </div>
            </div>
          </div>
          : productListStateInstance.products.map(item => {
            return <div className="col-12 col-md-6 mt-4" key={`pi-${item.id}`}>
              <div className="product-list__product-card-substrate">
                <div className="row">
                  <div className='col-12 col-sm-6'>
                    <ImageSlider images={item.images} />
                  </div>

                  <div className='col-12 col-sm-6'>
                    <div>
                      <p className="small">{ item.productCategory ? (item.productCategory['name'] ?? '') : '' }</p>
                    </div>
                    <div>
                      <p className='h4'>{`${ [ item.vendor ? (item.vendor['name'] ?? '') : '', item.name].join(' ').trim() }`}</p>
                    </div>
                    <div>
                      <div style={{display: 'flex', justifyContent: 'space-between'}}>
                        <div>
                          <p className="b">Артикул</p>
                        </div>

                        <div>
                          <p>{`${item.code}`}</p>
                        </div>

                      </div>
                    </div>
                    <div>
                      <div style={{display: 'flex', justifyContent: 'space-between'}}>
                        <div>
                          <p className="b">Цена</p>
                        </div>

                        <div>
                          <p>{`${item.price}`}</p>
                        </div>

                      </div>
                    </div>
                    <div>
                      <div style={{display: 'flex', justifyContent: 'space-between'}}>
                        <div>
                          <p className="b">Количество</p>
                        </div>

                        <div>
                          <p>{`${item.count}`}</p>
                        </div>

                      </div>
                    </div>
                    <div>
                      <a href={`/control-panel/product-card?id=${item.id}`} target={'_blank'}>Редактировать</a>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          })
        }
      </div>

      <div className="row">
        <div className="col-12">
          <div className="card m-t-30">
            <div className="card-body">
              <div style={{ display: 'flex', justifyContent: 'center' }}>
                <div>
                  <Navigator />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
});

const renderProductList = () => {
  const root = document.getElementById('product-list-root');
  if (root !== null) {
    productListStateInstance.init();
    ReactDOM.render(<ProductList />, root);
  }
};

export default renderProductList;
