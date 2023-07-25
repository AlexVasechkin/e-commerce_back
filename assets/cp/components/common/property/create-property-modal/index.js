import React, { useState } from 'react';
import {Modal} from 'react-bootstrap';
import CreatePropertyForm from './create-property-form';


const CreatePropertyModal = ({
  btnClasses = '',
  onSuccess = id => {},
  buttonCaption = 'Добавить'
}) => {
  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  return <>
    <button className={ btnClasses }
            onClick={ show } ><i className="fa fa-plus" /> { buttonCaption }</button>

    <Modal show={ visible }
           onHide={close}>
      <Modal.Header closeButton>
        <Modal.Title>Добавление свойства</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreatePropertyForm onSuccess={ onSuccess } />
      </Modal.Body>

    </Modal>
  </>
};

export default CreatePropertyModal;
