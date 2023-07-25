import React, {useState} from 'react';


const UploadImageForm = ({
  uploadImage,
  handleClose
}) => {

  const [image, setImage] = useState();
  const [resultScreen, setResultScreen] = useState(null);

  const onSubmit = e => {
    e.preventDefault();
    setResultScreen(uploadImage(image, handleClose));
  };

  const getForm = () => <form onSubmit={ onSubmit }>
    <div className="form-group">
      <input className="form-control"
             required
             type="file"
             onChange={ e => setImage(e.target.files[0]) }
      />
    </div>

    <div className="form-group">
      <button type="submit" className="btn btn-outline-info">Загрузить</button>
    </div>
  </form>;

  return resultScreen ?? getForm();
};

export default UploadImageForm;
