import React, { useState } from 'react';
import { Modal } from 'react-bootstrap';
import CreateProductForm from './components/create-product-form';


const CreateProductModal = ({
    getVendors = () => [],
    btnClasses = '',
    onSuccess = id => null,
    productCategoryId = null
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
                <Modal.Title>Создание товара</Modal.Title>
            </Modal.Header>

            <Modal.Body>
                <CreateProductForm getVendors={ getVendors }
                                   onSuccess={ onSuccess }
                                   productCategoryId={ productCategoryId }
                />
            </Modal.Body>

        </Modal>
    </>
};

export default CreateProductModal;
