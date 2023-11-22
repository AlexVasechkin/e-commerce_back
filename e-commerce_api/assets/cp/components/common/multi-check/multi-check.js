import React, { useState } from 'react';
import MultiCheckItem from './components/multi-check-item';


const MultiCheck = ({
  checkedItems,
  availableItems,
  onCheck,
  onUncheck,
  label = ''
}) => {

  const [searchPattern, setSearchPattern] = useState('');

  return (
    <>
      <div className="m-b-5"><span className="small">{`${ label }`}</span></div>

      <div>
        {
          checkedItems.map(item => {
              return (
                <div key={ item.id }>
                  <MultiCheckItem onClick={ () => onUncheck(item.id) }
                                  label={ item.caption }
                                  checked={ true }
                  />
                </div>
              )
            }
          )
        }
      </div>

      <hr/>

      <div>
        <div>
          <div className="form-group">
            <input type="text"
                   className="form-control"
                   value={ searchPattern }
                   onChange={ e => setSearchPattern(e.target.value) }
            />
          </div>
        </div>

        <div style={{ overflowY: 'auto', maxHeight: '135px' }}>
          {   searchPattern === ''
            ? (
                availableItems.map(item => {
                  return (
                    <div key={ item.id }>
                      <MultiCheckItem onClick={ () => onCheck(item.id) }
                                      label={ item.caption }
                                      checked={ false }
                      />
                    </div>
                  )
                })
              )
            : availableItems
                .filter(el => (el.caption.indexOf(searchPattern) > -1))
                .map(item => {
                  return (
                    <div key={ item.id }>
                      <MultiCheckItem onClick={ () => onCheck(item.id) }
                                      label={ item.caption }
                                      checked={ false }
                      />
                    </div>
                  )
                })
          }
        </div>
      </div>
    </>
  );
};

export default MultiCheck;
