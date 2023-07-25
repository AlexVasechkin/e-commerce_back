import React from 'react';
import {observer} from 'mobx-react-lite';
import propertyEditorStateInstance from './state';
import {Tabs, Tab, Spinner} from 'react-bootstrap';


const PropertyEditor = observer(() => {

  const intControl = ({
    propertyId,
    value = null,
    onChange = () => {},
    onBlur = () => {},
    label = ''
  }) => <div className="col-12 col-sm-6 m-t-15" key={ `pc-${ propertyId }` }>
    <div className="form-group">
      <label>{ label }</label>
      <input type="number"
             className="form-control"
             value={ value }
             onChange={ onChange }
             step={ 1 }
             onBlur={ onBlur }
      />
    </div>
  </div>;

  const floatControl = ({
    propertyId,
    value = null,
    onChange = () => {},
    onBlur = () => {},
    label = ''
  }) => <div className="col-12 col-sm-6 m-t-15" key={ `pc-${ propertyId }` }>
      <div className="form-group">
        <label>{ label }</label>
        <input type="number"
               className="form-control"
               value={ value }
               onChange={ onChange }
               step={ .01 }
               onBlur={ onBlur }
        />
      </div>
    </div>
  ;

  const stringControl = ({
    propertyId,
    value = null,
    onChange = () => {},
    onBlur = () => {},
    label = ''
  }) => <div className="col-12 col-sm-6 m-t-15" key={ `pc-${ propertyId }` }>
    <div className="form-group">
      <label>{ label }</label>
      <input type="text"
             className="form-control"
             value={ value }
             onChange={ onChange }
             onBlur={ onBlur }
      />
    </div>
  </div>;

  const boolControl = ({
    propertyId,
    value = null,
    onChange = () => {},
    label = ''
  }) => <div className="col-12 col-sm-6 m-t-15" key={ `pc-${ propertyId }` }>
    <div className="form-group">
      <label>{ label }</label>
      <select onChange={ onChange } value={ value } className="form-control">
        <option value={ 'nul' }>Не указано</option>
        <option value={ 'true' }>Да</option>
        <option value={ 'false' }>Нет</option>
      </select>
    </div>
  </div>;

  const getCategories = () => [...new Set(propertyEditorStateInstance.properties.map(({ groupName = '' }) => groupName))];

  const renderTabs = (categories) => {
    return <Tabs defaultActiveKey={ categories[0] }>
      { categories.map(cat => {
        return <Tab eventKey={ cat } title={ cat } ><div className="row">{ renderControls(propertyEditorStateInstance.properties.filter(el => el.groupName === cat)) }</div></Tab>
      }) }
    </Tabs>;
  };

  const renderControls = properties => properties.map(item => {

    const {
      type = '',
      propertyId = 0,
      propertyName = '',
      unit = null
    } = item;

    const save = () => propertyEditorStateInstance.save({
      productId: propertyEditorStateInstance.id,
      propertyId,
      value: item.value
    });

    const commonProps = {
      label: `${ propertyName }${ unit ? (', ' + unit) : '' }`,
      propertyId
    };

    if (type === 'int') {
      return intControl({
        ...commonProps,
        ...{
          value: item.value === null ? '' : `${ item.value }`,
          onChange: e => {
            if (e.target.value === '') {
              item.value = null;
            } else {
              try {
                item.value = parseInt(e.target.value);
              } catch (e) {
                console.error(e);
              }
            }
          },
          onBlur: () => save()
        }
      });
    } else if (type === 'float') {
      return floatControl({
        ...commonProps,
        ...{
          value: item.value === null ? '' : `${item.value}`,
          onChange: e => {
            if (e.target.value === '') {
              item.value = null;
            } else {
              try {
                item.value = parseFloat(e.target.value);
              } catch (e) {
                console.error(e);
              }
            }
          },
          onBlur: () => save()
        }
      });
    } else if (type === 'string') {
      return stringControl({
        ...commonProps,
        ...{
          value: item.value === null ? '' : `${item.value}`,
          onChange: e => item.value = `${e.target.value}`,
          onBlur: () => save()
        }
      });
    } else if (type === 'bool') {
      return boolControl({
        ...commonProps,
        ...{
          value: item.value === null ? 'nul' : `${item.value}`,
          onChange: e => {
            if (e.target.value === 'nul') {
              item.value = null;
            } else if (e.target.value === 'true') {
              item.value = true;
            } else if (e.target.value === 'false') {
              item.value = false;
            }

            save()
          }
        }
      })
    } else {
      return null;
    }
  });

  return <>{ propertyEditorStateInstance.isAwait ? <Spinner animation={ 'border' } /> : renderTabs(getCategories()) }</>;
});

export default PropertyEditor;
