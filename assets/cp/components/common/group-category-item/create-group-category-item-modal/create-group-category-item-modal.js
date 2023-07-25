import React, { useState } from 'react';
import {Modal} from 'react-bootstrap';

import CreateGroupCategoryItemForm from './create-group-category-item-form';


const CreateGroupCategoryItemModal = ({
  getTriggerComponent,
  onSuccess,
  productGroupDict,
  productCategoryDict,
  initCategoryId
}) => {

  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  const handleSuccess = () => {
    onSuccess(close);
  };

  return <>
    { getTriggerComponent(show) }

    <Modal show={ visible }
           onHide={close}>

      <Modal.Header closeButton>
        <Modal.Title>Добавить группу товаров в категорию</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreateGroupCategoryItemForm onSuccess={ handleSuccess }
                                     productGroupDict={ productGroupDict }
                                     productCategoryDict={ productCategoryDict }
                                     initCategoryId={ initCategoryId }
        />
      </Modal.Body>

    </Modal>
  </>;
};

export default CreateGroupCategoryItemModal;
