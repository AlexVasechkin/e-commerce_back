import React, {useState} from 'react';
import {Modal} from 'react-bootstrap';
import CreateProductPageForm from './create-product-page-form';


const CreateProductPageModal = ({
  btnClasses = '',
  onSuccess = id => {},
  productId
}) => {

  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  return <>
    <button className={ btnClasses }
            onClick={ show } ><i className="fa fa-plus" /> Добавить</button>

    <Modal show={ visible }
           onHide={close}>
      <Modal.Header closeButton>
        <Modal.Title>Добавление страницы товара</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreateProductPageForm onSuccess={ onSuccess }
                               productId={ productId } />
      </Modal.Body>

    </Modal>
  </>
};

export default CreateProductPageModal;
