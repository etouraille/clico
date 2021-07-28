import {Picture} from "./picture";

export interface Product{
  uuid?: string;
  name: string;
  label?: string;
  price: number;
  pictures?: Picture[];
}
