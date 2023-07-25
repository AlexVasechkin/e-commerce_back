import {makeAutoObservable, observe} from 'mobx';
import productListStateInstance from '../../state';


class ProductListFilterState {

  categoryId = null

  vendorIdList = '[]'

  productGroupId = null

  productPageActive = null

  productHasPage = null

  constructor() {
    makeAutoObservable(this);
  }

  getVendorIdList() {
    return JSON.parse(this.vendorIdList);
  }

  getAllFilterValues() {
    return {
      categoryId: this.categoryId,
      vendorIdList: this.getVendorIdList(),
      productGroupIdList: this.productGroupId === null ? null : [this.productGroupId],
      productPageActive: this.productPageActive,
      productHasPage: this.productHasPage
    }
  }

  addToVendorList(id) {
    const vendorIdList = this.getVendorIdList();
    vendorIdList.push(id);
    this.vendorIdList = JSON.stringify(vendorIdList);
  }

  removeFromVendorList(id) {
    const vendorIdList = this.getVendorIdList();
    this.vendorIdList = JSON.stringify(vendorIdList.filter(v => v !== id));
  }

  reloadProducts() {
    productListStateInstance.reloadProducts();
  }

  convertBooleanValue(v) {
    switch (v) {
      case '':
        return null;
      case '1':
        return true;
      case '0':
        return false;
    }
  }

  setProductPageActive(v) {
    this.productPageActive = this.convertBooleanValue(v);
  }

  setHasProductPage(v) {
    this.productHasPage = this.convertBooleanValue(v);
  }
}

const productListFilterStateInstance = new ProductListFilterState();

observe(productListFilterStateInstance, change => {
  if (['categoryId', 'vendorIdList', 'productGroupId', 'productPageActive', 'productHasPage'].includes(change.name)) {
    productListFilterStateInstance.reloadProducts();
  }
});

export default productListFilterStateInstance;
