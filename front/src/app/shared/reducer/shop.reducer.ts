import { createReducer, on } from '@ngrx/store';
import { setShop } from '../action/shop.action';

const initialState = { shop : {}};

const _shopReducer = createReducer(
  initialState,
  on(setShop, (state, { shop }) => {
    state.shop = shop;
    return state;
  }),
);

export function shopReducer(state, action) {
  return _shopReducer(state, action);
}
