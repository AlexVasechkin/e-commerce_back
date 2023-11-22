import React, { useState } from 'react';
import {Modal} from 'react-bootstrap';

import UpdateProductGroupForm from './update-product-group-form';


const UpdateProductGroupModal = ({
  id,
  getTriggerComponent,
  onSuccess
}) => {
  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  const handleSuccess = () => {
    onSuccess(close);
  };

  return (
    <>
      { getTriggerComponent(show) }

      <Modal show={ visible }
             onHide={close}>
        <Modal.Header closeButton>
          <Modal.Title>Добавление производителя</Modal.Title>
        </Modal.Header>

        <Modal.Body>
          <UpdateProductGroupForm id={ id }
                                  onSuccess={ handleSuccess }
          />
        </Modal.Body>

      </Modal>
    </>
  )
};

export default UpdateProductGroupModal;
