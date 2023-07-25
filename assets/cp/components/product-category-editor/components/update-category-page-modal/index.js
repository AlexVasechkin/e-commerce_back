import React from 'react';
import UpdateWebpageModal from '../../../common/webpage/update-webpage-modal';
import productCategoryEditorStateInstance from '../../state';


const ProductCategoryEditorUpdateCategoryPageModal = ({
  id
}) => {

  const triggerComponent = show => {
    return <button className="btn btn-outline-light" onClick={ () => show() }>Изменить</button>
  };

  return <UpdateWebpageModal id={ id }
                             getTriggerComponent={ triggerComponent }
                             onSuccess={ () => productCategoryEditorStateInstance.reloadProductCategories() }
  />;
};

export default ProductCategoryEditorUpdateCategoryPageModal;
