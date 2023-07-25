import { makeAutoObservable, observe } from 'mobx';
import axios from 'axios';

import productListFilterStateInstance from './components/filter/state';


class ProductListState {

  isAwait = false

  currentPage = null

  totalPageCount = 1

  limit = 12

  products = []

  vendors = []

  productCategories = []

  productGroups = []

  searchString = ''

  reloadProducts() {

      this.isAwait = true;

      axios
          .post('/api/v1/private/products', {
            filters: {
              ...productListFilterStateInstance.getAllFilterValues(),
              ...{searchString: this.searchString}
            },
            currentPage: this.currentPage,
            limit: this.limit
          })
          .then(({ data = {} }) => {
              const {
                  payload = [],
                  totalPageCount = 1
              } = data;

              this.products = payload;
              this.totalPageCount = totalPageCount > 0 ? totalPageCount : 1;
          })
          .finally(() => {
              this.isAwait = false;
          })
  }

  reloadVendors() {
    axios
      .post('/api/v1/private/vendor/dict')
      .then(({ data = {} }) => {
          const { payload = [] } = data;
          this.vendors = payload.map(item => {
              return {
                  value: item.id,
                  label: item.name
              };
          });
      });
  }

  reloadProductCategories() {
    axios
      .get('/api/v1/private/product-category/dict')
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.productCategories = payload.map(item => {
          return {
            value: item.value,
            label: item.caption
          };
        });
      });
  }

  reloadProductGroups() {
    axios
      .post('/api/v1/public/product-group/list', {})
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.productGroups = payload.map(item => ({
          value: item.id,
          label: item.name
        }));
      });
  }

  constructor() {
    makeAutoObservable(this);
  }

  init() {
    this.reloadVendors();
    this.reloadProductCategories();
    this.reloadProductGroups();
  };
}

const productListStateInstance = new ProductListState();

observe(productListStateInstance, change => {
  if (['filters', 'currentPage'].includes(change.name)) {
    productListStateInstance.reloadProducts();
  }
});

export default productListStateInstance;
