<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author fdevbt
 */
interface IDataModifier {
    public function update($arr);
    
    public function insert($arr);
    
    public function delete($arr);
    
    public function select($query);
    
    public function begin();
    
    public function apply();
    
    public function discard();
}
