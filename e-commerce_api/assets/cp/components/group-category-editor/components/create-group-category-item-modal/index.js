import React from 'react';

import CreateGroupCategoryItemModal from '../../../common/group-category-item/create-group-category-item-modal';
import groupCategoryEditorStateInstance from '../../state';


const GroupCategoryEditorCreateItemModal = ({
  productGroupDict,
  productCategoryDict,
  initCategoryId = null
}) => {

  const getTriggerComponent = (show) => {
    return (
      <button onClick={ show }
              className="btn btn-outline-info"
      ><i className="fa fa-plus" /> Add</button>
    )
  }

  const onSuccess = (close) => {
    groupCategoryEditorStateInstance.reloadProductGroupCategoryItems();
    close();
  }

  return <CreateGroupCategoryItemModal productGroupDict={ productGroupDict }
                                       productCategoryDict={ productCategoryDict }
                                       getTriggerComponent={ getTriggerComponent }
                                       onSuccess={ onSuccess }
                                       initCategoryId={ initCategoryId }
  />
};

export default GroupCategoryEditorCreateItemModal;
