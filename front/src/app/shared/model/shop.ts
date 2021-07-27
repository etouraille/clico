import {Address} from "./address";

export interface Shop {
  uuid?: string;
  name: string;
  type: string;
  address: Address;
  file?: string;
  email?: string;
  phone?: string;
  mobile?: string;
}
