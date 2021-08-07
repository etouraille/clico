import {AfterViewInit, Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {deleteProduct, Product, ProductService, Shop} from "@shared";
import {Store} from "@ngrx/store";
import {HttpClient} from "@angular/common/http";
import {ProductsDataSource} from "../../../shared/data-source/products.data-source";
import {MatPaginator} from "@angular/material/paginator";
import {MatSort} from "@angular/material/sort";
import {fromEvent, merge, Observable} from "rxjs";
import {debounceTime, distinctUntilChanged, tap} from "rxjs/operators";
import {MatTableDataSource} from "@angular/material/table";
import {DataSource} from "@angular/cdk/collections";

@Component({
  selector: 'app-products',
  templateUrl: './products.component.html',
  styleUrls: ['./products.component.css']
})
export class ProductsComponent implements OnInit , AfterViewInit {

  n: Observable<number>;
  shop: Shop;
  products: Product[] = [];
  filteredProduct: MatTableDataSource<Product> = new MatTableDataSource([]);
  dataSource: ProductsDataSource;
  displayedColumns: string[] = ['picture', 'name', 'label', 'price', 'action'];

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  @ViewChild('input') input: ElementRef;


  constructor(
    private router: Router,
    private route: ActivatedRoute,
    private store: Store<{shop: Shop, product: Product[]}>,
    private http: HttpClient,
    private productService: ProductService,
  )
  { }

  ngOnInit(): void {
    this.setShopAndproduct();
    // this.dataSource = new ProductsDataSource(this.productService);
    // this.dataSource.loadProducts(this.shop.uuid, '', 'asc', 0, 3);
    // this.n = this.dataSource.n;


  }


  ngAfterViewInit() {

    this.setFilterProduct();

    // server-side search
    fromEvent(this.input.nativeElement,'keyup')
      .pipe(
        debounceTime(150),
        distinctUntilChanged(),
        tap(() => {
          this.paginator.pageIndex = 0;
          this.loadProductsPage();
        })
      )
      .subscribe();



    // reset the paginator after sorting
    //this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);

    // on sort or paginate events, load a new page
    /*
    merge(this.sort.sortChange, this.paginator.page)
      .pipe(
        tap(() => this.loadProductsPage())
      )
      .subscribe();
    */
  }


  loadProductsPage() {
    this.dataSource.loadProducts(
      this.shop.uuid,
      this.input.nativeElement.value,
      this.sort.direction,
      this.paginator.pageIndex,
      this.paginator.pageSize);
  }



  createProduct() : void {
    this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid + '/je-creee-un-produit']);
  }

  private setShopAndproduct(): void {
    this.store.select('shop').subscribe((data: any)=> {
      this.shop = data.shop;
    })
    this.store.select('product').subscribe((data: any) => {
      console.log( data.products );
      this.products = data.products;
      this.setFilterProduct();
    });
  }

  navigate(uuid: string ): void {
    this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid + '/produit/' + uuid])
  }

  onDeleteProduct(uuid: string): void {
    this.http.delete('/api/product/' + uuid).subscribe(() => {
      this.store.dispatch(deleteProduct({uuid}));
    })
  }

  onPageChange(event: any) {
    console.log( event );
    // this.setFilterProduct();
  }

  setFilterProduct(): void {
    this.filteredProduct = new MatTableDataSource(this.products);
    this.filteredProduct.paginator = this.paginator;
  }
}
