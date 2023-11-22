import React, {useState} from 'react';
import {Modal} from 'react-bootstrap';
import CreateCategoryPropertyForm from './create-category-property-form';


const CreateCategoryPropertyModal = ({
  btnClasses = '',
  onSuccess = id => {},
  categories = [],
  properties = []
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
        <Modal.Title>Добавление свойства</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreateCategoryPropertyForm categories={ categories }
                                    properties={ properties }
                                    onSuccess={ onSuccess }
        />
      </Modal.Body>

    </Modal>
  </>
};

export default CreateCategoryPropertyModal;
