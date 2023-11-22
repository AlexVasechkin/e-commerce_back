import React, { useState } from 'react';
import { Modal } from 'react-bootstrap';
import UploadImageForm from './upload-image-form';


const UploadImageModal = ({
  triggerComponent,
  uploadImage
}) => {

  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  return <>
    {triggerComponent(show)}

    <Modal show={ visible }
           onHide={close}>
      <Modal.Header closeButton>
        <Modal.Title>Добавление изображения</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <UploadImageForm uploadImage={uploadImage}
                         handleClose={close} />
      </Modal.Body>

    </Modal>
  </>
};

export default UploadImageModal;
