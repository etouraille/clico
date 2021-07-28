import { createAction, props } from '@ngrx/store';
import {Shop} from "../model";

// @ts-ignore
// @ts-ignore
export const setShop = createAction('[addSop]', props<{ shop: Shop }>());
