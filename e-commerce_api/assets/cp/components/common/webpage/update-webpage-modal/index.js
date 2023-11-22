import React, { useState } from 'react';
import {Modal} from 'react-bootstrap';
import UpdateWebpageForm from './update-webpage-form';


const UpdateWebpageModal = ({
  id,
  getTriggerComponent,
  onSuccess
}) => {
  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  return <>
    { getTriggerComponent(show) }

    <Modal show={ visible }
           onHide={close}>
      <Modal.Header closeButton>
        <Modal.Title>Добавление производителя</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <UpdateWebpageForm id={ id } onSuccess={ onSuccess } />
      </Modal.Body>

    </Modal>
  </>;
};

export default UpdateWebpageModal;
