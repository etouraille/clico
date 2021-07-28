import { createAction, props } from '@ngrx/store';
import {Product} from "../model";


export const addProduct = createAction('[addProduct]', props<{ product: Product }>());
export const addProducts = createAction('[addProducts]', props<{ products: Product[] }>());
export const deleteProduct = createAction('[deleteProduct]', props<{ uuid: string }>());
