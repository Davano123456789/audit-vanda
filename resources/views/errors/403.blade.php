@extends('errors.layout')

@section('title', 'Akses Ditolak')
@section('code', '403')
@section('icon', 'shield-x')
@section('message', $exception->getMessage() ?: 'Anda tidak memiliki izin untuk membuka halaman ini.')
