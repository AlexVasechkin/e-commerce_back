import React from 'react';
import {observer} from 'mobx-react-lite';

import CreateProductGroupModal from '../../../common/product-group/create-product-group-modal';
import productGroupEditorStateInstance from './state';
import ProductEditorUpdateProductGroupModal from './components/update-product-group-modal';


const ProductCardProductGroupEditor = observer(() => {

  const TriggerComponent = (show) => <button className="btn btn-outline-info btn-block"
                                             onClick={ show }
    >Добавить группу</button>
  ;

  const onSuccess = () => productGroupEditorStateInstance.reloadProductGroupList();

  return <div>
    <div>
      {
        productGroupEditorStateInstance.productGroups.map(item => {
          return <div key={`pg-${item.id}`}>
            <label><input type="checkbox"
                          checked={ item.isInGroup }
                          onClick={ () => productGroupEditorStateInstance.changeInGroup(item.id) }
            /> {`${item.name}`}</label> <ProductEditorUpdateProductGroupModal id={ item.id } />
          </div>
        })
      }
    </div>

    <hr/>

    <div>
      <CreateProductGroupModal getTriggerComponent={ TriggerComponent }
                               onSuccess={ onSuccess } />
    </div>

  </div>;

});

export default ProductCardProductGroupEditor;
