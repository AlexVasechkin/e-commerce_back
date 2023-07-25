import React, {useState} from 'react';
import {Modal} from 'react-bootstrap';
import CreateProductCategoryForm from './create-product-category-form';


const CreateProductCategoryModal = ({
  btnClasses = '',
  onSuccess = id => {},
  productCategoryDict
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
        <Modal.Title>Добавление категории товаров</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreateProductCategoryForm productCategoryDict={ productCategoryDict }
                                   onSuccess={ onSuccess }
        />
      </Modal.Body>

    </Modal>
  </>;
};

export default CreateProductCategoryModal;
