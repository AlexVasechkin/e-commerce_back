import React, { useState } from 'react';
import {Modal} from 'react-bootstrap';

import CreateProductGroupForm from './create-product-group-form';


const CreateProductGroupModal = ({
  getTriggerComponent,
  onSuccess
}) => {
  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  const handleSuccess = (id) => {
    onSuccess(id);
    close();
  };

  return <>
    { getTriggerComponent(show) }

    <Modal show={ visible }
           onHide={close}>
      <Modal.Header closeButton>
        <Modal.Title>Новая группа товаров</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreateProductGroupForm onSuccess={ handleSuccess } />
      </Modal.Body>

    </Modal>
  </>;
};

export default CreateProductGroupModal;
