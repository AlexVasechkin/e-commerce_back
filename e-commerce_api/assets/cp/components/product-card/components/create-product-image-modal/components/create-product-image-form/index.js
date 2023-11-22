import React, {useState} from 'react';
import axios from 'axios';


const CreateProductImageForm = ({
  productId,
  onHandleClose = () => {},
  onAfterSuccess = () => {}
}) => {

  const [image, setImage] = useState();
  const [imageId, setImageId] = useState(null);

  const onSubmit = e => {
    e.preventDefault();

    const formData = new FormData();

    formData.append('image', image);
    formData.append('productId', productId);

    axios({
      method: 'post',
      url: '/api/v1/private/product-image/create',
      data: formData,
      headers: { "Content-Type": "multipart/form-data" }
    })
      .then(({ data = {} }) => {

        const { id = null } = data;

        if (id) {
          setImageId(id);
        }

        onAfterSuccess();
      })
  };

  const resetModal = () => {
    setImage(null);
    setImageId(null);
  };

  const getForm = () => <form onSubmit={ onSubmit } >
    <div className="form-group">
      <input className="form-control" required type="file" onChange={ e => setImage(e.target.files[0]) } />
    </div>

    <div className="form-group">
      <button type="submit" className="btn btn-outline-info">Создать</button>
    </div>
  </form>;

  const getSuccessMessage = () => <div>
    <div className="text-center m-b-10">Картинка успешно добавлена.</div>
    <div className="text-center m-b-10"><button onClick={ onHandleClose } className="btn btn-outline-info">Закрыть</button></div>
    <div className="text-center m-b-10"><button onClick={ resetModal } className="btn btn-outline-light">Добавить еще</button></div>
  </div>;

  return <>{ imageId ? getSuccessMessage() : getForm() }</>;
};

export default CreateProductImageForm;
