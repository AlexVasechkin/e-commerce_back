import React from 'react';
import { observer } from 'mobx-react-lite';
import Select from 'react-select';
import MultiCheck from '../../../common/multi-check';
import productListFilterStateInstance from './state';


const ProductListFilter = observer(({
  productCategories,
  vendors,
  productGroups,
  disabled = false
}) => {

  return (
    <>
      <div>
        <Select options={productCategories}
                isMulti={false}
                onChange={v => productListFilterStateInstance.categoryId = v ? parseInt(v.value) : null}
                isClearable
                placeholder={'Категория'}
                value={ productCategories.find(el => el.value === productListFilterStateInstance.categoryId) }
                disabled={ disabled }
        />
      </div>

      <div className="m-t-15">
        <MultiCheck availableItems={ vendors
                      .filter(el => !productListFilterStateInstance.getVendorIdList().includes(el.value))
                      .map(item => {
                        return {
                          id: parseInt(item.value),
                          caption: item.label
                        }
                      })
                    }
                    checkedItems={ vendors
                      .filter(el => productListFilterStateInstance.getVendorIdList().includes(el.value))
                      .map(item => {
                        return {
                          id: parseInt(item.value),
                          caption: item.label
                        }
                      })
                    }
                    onCheck={ id => productListFilterStateInstance.addToVendorList(id) }
                    onUncheck={ id => productListFilterStateInstance.removeFromVendorList(id)}
                    label={ 'Производители' }
        />
      </div>

      <div className="m-t-15">
        <label>Группа товаров</label>
        <Select options={ productGroups }
                isMulti={false}
                onChange={ v => productListFilterStateInstance.productGroupId = (v === null ? null : parseInt(v.value)) }
                isClearable
                placeholder={ 'Группа товаров' }
                value={ productListFilterStateInstance.productGroupId === null ? null : productGroups.find(el => el.value === productListFilterStateInstance.productGroupId) }
                disabled={ disabled }
        />
      </div>

      <div className="m-t-15">
        <label>Есть страница</label>
        <div className="form-group">
          <select className="form-control"
                  onChange={ e => productListFilterStateInstance.setHasProductPage(e.target.value) }
          >
            <option value={ '' }>Не фильтровать</option>
            <option value={ '1' }>Есть</option>
            <option value={ '0' }>Нет</option>
          </select>
        </div>
      </div>

      <div className="m-t-15">
        <label>Страница товара</label>
        <div className="form-group">
          <select className="form-control"
                  onChange={ e => productListFilterStateInstance.setProductPageActive(e.target.value) }
          >
            <option value={ '' }>Не фильтровать</option>
            <option value={ '1' }>Активна</option>
            <option value={ '0' }>Неактивна</option>
          </select>
        </div>
      </div>



      {/*<div className="m-t-15">*/}
      {/*  <MultiCheck availableItems={ productGroups*/}
      {/*                .filter(el => !productListFilterStateInstance.getProductGroupIdList().includes(el.value))*/}
      {/*                .map(item => {*/}
      {/*                  return {*/}
      {/*                    id: parseInt(item.id),*/}
      {/*                    caption: item.caption*/}
      {/*                  }*/}
      {/*                })*/}
      {/*              }*/}
      {/*              checkedItems={ productGroups*/}
      {/*                .filter(el => productListFilterStateInstance.getProductGroupIdList().includes(el.value))*/}
      {/*                .map(item => {*/}
      {/*                  return {*/}
      {/*                    id: parseInt(item.id),*/}
      {/*                    caption: item.caption*/}
      {/*                  }*/}
      {/*                })*/}
      {/*              }*/}
      {/*              onCheck={ id => productListFilterStateInstance.addToProductGroupList(id)}*/}
      {/*              onUncheck={ id => productListFilterStateInstance.removeFromProductGroupList(id)}*/}
      {/*              label={ 'Группы товаров' }*/}
      {/*  />*/}
      {/*</div>*/}



    </>
  )
});

export default ProductListFilter;
