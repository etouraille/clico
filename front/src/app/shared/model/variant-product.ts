import {Label} from "./label";
import {Picture, Product} from "@shared";

export interface VariantProduct {
  id: number;
  label: string;
  pictures: Picture[];
  price: number;
  labels: Label[];
  product: Product;
}
