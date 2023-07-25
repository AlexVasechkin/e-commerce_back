import React, {useState} from 'react';


const ImageSlider = ({
  images
}) => {
  const [currentIndex, setCurrentIndex] = useState(0);

  const getCurrentImage = () => images
    .sort((a, b) => {
      if (a.sortOrder < b.sortOrder) {
        return -1;
      }
      if (a.sortOrder > b.sortOrder) {
        return 1;
      }
      return 0;
    })[currentIndex] ?? null
  ;

  return <>
    <div>
      <div className="fixed-frame-container">
        <div className="fixed-frame-inner-container">
          <div>
            <img className='adaptive-content border-container'
                 src={ getCurrentImage() !== null ? getCurrentImage().path : '' }
                 alt=""
            />
          </div>
        </div>
      </div>
    </div>

    <div>
      <div style={ { display: 'flex', alignItems: 'center', justifyContent: 'center' } }>
        {images.map((item, index) => {
          return <div style={{display: 'block', margin: '5px'}} key={`sl-i-${item.id}`}>
            <div style={ { width: '20px', height: '20px', borderRadius: '20px', backgroundColor: index === currentIndex ? '#d3d3d3' : '#f1f1f1' } }
                 onClick={ () => setCurrentIndex(index) }
            ></div>
          </div>
        })}
      </div>
    </div>
  </>
};

export default ImageSlider;
