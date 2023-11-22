import React, {useState} from 'react';
import CreateVendorForm from './create-vendor-form';
import {Modal} from 'react-bootstrap';


const CreateVendorModal = ({
  btnClasses = '',
  onSuccess = id => {}
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
        <Modal.Title>Добавление производителя</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreateVendorForm onSuccess={ onSuccess } />
      </Modal.Body>

    </Modal>
  </>
}

export default CreateVendorModal;
