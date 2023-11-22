import React from 'react';

import UpdateProductGroupModal
  from '../../../../../common/product-group/update-product-group-modal';


const ProductEditorUpdateProductGroupModal = ({id}) => {

  const TriggerComponent = (show) => <span onClick={ () => show() }>изменить</span>;

  return (
    <UpdateProductGroupModal id={ id }
                             getTriggerComponent={ TriggerComponent }
                             onSuccess={ (close) => close() }
    />
  );
};

export default ProductEditorUpdateProductGroupModal;
