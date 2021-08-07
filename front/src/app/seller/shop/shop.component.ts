import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router, RouterStateSnapshot} from "@angular/router";
import {Shop} from "@shared";

@Component({
  selector: 'app-shop',
  templateUrl: './shop.component.html',
  styleUrls: ['./shop.component.css']
})
export class ShopComponent implements OnInit {

  shop: Shop;
  active: boolean = true;

  constructor(private route: ActivatedRoute, private router: Router) { }

  ngOnInit(): void {
  }
}
