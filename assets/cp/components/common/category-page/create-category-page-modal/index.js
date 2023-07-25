import React, {useState} from 'react';
import {Modal} from 'react-bootstrap';
import CreateCategoryPageForm from './create-category-page-form';


const CreateCategoryPageModal = ({
  categoryId,
  triggerComponent,
  onSuccess
}) => {
  const [visible, setVisible] = useState(false);

  const close = () => setVisible(false);
  const show = () => setVisible(true);

  return <>
    { triggerComponent(show) }

    <Modal show={ visible }
           onHide={close}>
      <Modal.Header closeButton>
        <Modal.Title>Создание страницы категории</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <CreateCategoryPageForm
          categoryId={ categoryId }
          onSuccess={ onSuccess }
        />
      </Modal.Body>

    </Modal>
  </>;
};

export default CreateCategoryPageModal;
