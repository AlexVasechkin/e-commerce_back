import React, {useState} from 'react';
import axios from 'axios';
import productCategoryEditorStateInstance from '../../../../../state';


const UploadProductCategoryImageModal = ({
  categoryId
}) => {
  const [image, setImage] = useState();

  const onSubmit = e => {
    e.preventDefault();

    const formData = new FormData();

    formData.append('image', image);
    formData.append('categoryId', categoryId);

    axios({
      method: 'post',
      url: '/api/v1/private/product-category/upload-image',
      data: formData,
      headers: { "Content-Type": "multipart/form-data" }
    })
      .then(() => {
        productCategoryEditorStateInstance.reloadProductCategories();
      })
  };

  return <form onSubmit={ onSubmit }>
    <div className="row">
      <div className="col-6">
        <input className="form-control-file mb-2"
               required
               type="file"
               onChange={ e => setImage(e.target.files[0]) }
        />
      </div>

      <div className="col-6">
        <button type="submit" className="btn btn-outline-info mb-2">Загрузить</button>
      </div>
    </div>
  </form>
};

export default UploadProductCategoryImageModal;
