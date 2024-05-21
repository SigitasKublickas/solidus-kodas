type Product =  {
    id:number;
    name:string;
    desc:string;
    price:number;
    delivery_time:number;
    stock:number;
    img_url:string;
    category_id:number;
    brand:string;
    model:string;
    condition:string;
    created_at:string;
    updated_at:string;
}
type Category = {
    id:number;
    name:string;
    img_url:string;
    path:string;
    parent_id:number;
    created_at:string;
    updated_at:string;
}&{cat_childs:Category[]};

type Filter = {
    name:string,
    table_name: string,
    items:{name:string,count:number}[]
}

