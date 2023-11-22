import React from 'react';


const PageNavigator = ({
  currentPage,
  totalPageCount,
  onFirstPageClick,
  onPrevPageClick,
  onNextPageClick,
  onLastPageClick,
  firstPageDisabled = false,
  prevDisabled = false,
  nextDisabled = false,
  lastPageDisabled = false
}) => {
  return (
    <>
      <div className="btn-group">
        <button onClick={ onFirstPageClick }
                className={ [...['btn', 'btn-outline-light'], ...[firstPageDisabled ? 'disabled' : '']].join(' ').trim() }
                disabled={ firstPageDisabled }
        ><i className="fa fa-chevron-left" /></button>

        <button onClick={ onPrevPageClick }
                className={ [...['btn', 'btn-outline-light'], ...[prevDisabled ? 'disabled' : '']].join(' ').trim() }
                disabled={ prevDisabled }
        ><i className="fa fa-arrow-left" /></button>

        <button className="btn btn-outline-light"
        >{ `${ currentPage }/${ totalPageCount }` }</button>

        <button onClick={ onNextPageClick }
                className="btn btn-outline-light"
                disabled={ nextDisabled }
        ><i className="fa fa-arrow-right" /></button>

        <button onClick={ onLastPageClick }
                className={ [...['btn', 'btn-outline-light'], ...[lastPageDisabled ? 'disabled' : '']].join(' ').trim() }
                disabled={ lastPageDisabled }
        ><i className="fa fa-chevron-right" /></button>
      </div>
    </>
  )
};

export default PageNavigator;
