import { createReducer, on } from '@ngrx/store';
import {addProduct, deleteProduct} from '../action/product.action';
import { addProducts } from '../action/product.action';

const initialState = { products : []};

const _productReducer = createReducer(
  initialState,
  on(addProduct, (state, { product }) => {
    const index = state.products.findIndex( elem => elem.uuid === product.uuid );
    if(index>=0) {
      state.products[index] = product;
    } else {
      state.products.push(product);
    }
    return state;
  }),
  on(addProducts, (state, { products }) => {
    state.products = products;
    return state;
  }),
  on(deleteProduct, (state, { uuid }) => {
    const index = state.products.findIndex( elem => elem.uuid === uuid );
    if (index>=0) {
      state.products.splice(index, 1);
    }
    return { products: state.products };
  }),
);

export function productReducer(state, action) {
  return _productReducer(state, action);
}
