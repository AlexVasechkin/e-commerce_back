import React, { useState } from 'react';
import { Modal } from 'react-bootstrap';
import CreateProductImageForm from './components/create-product-image-form';
import productImageEditorStateInstance from '../product-image-editor/state';


const CreateProductImageModal = ({
  productId,
  btnClasses = ''
}) => {

  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  return <>
    <button className={ btnClasses }
            onClick={ show } ><i className="fa fa-plus" />&nbsp;Загрузить</button>

    <Modal show={ visible }
           onHide={close}>
      <Modal.Header closeButton>
        <Modal.Title>Добавление изображения</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreateProductImageForm productId={ productId }
                                onHandleClose={ close }
                                onAfterSuccess={ () => productImageEditorStateInstance.reloadImages() }
        />
      </Modal.Body>

    </Modal>
  </>
};

export default CreateProductImageModal;
