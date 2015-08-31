<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'register_records', function( $table ){
			$table->increments( 'id' );
			$table->integer( 'status' );
			$table->float( 'fee' );
			$table->text( 'advice' )->nullable();
			$table->datetime( 'start' )->nullable();
			$table->date( 'return_date' )->nullable();
			$table->integer( 'doctor_id' )->unsigned();
			$table->integer( 'account_id' )->unsigned();
			$table->integer( 'user_id' )->unsigned();
			$table->integer( 'period_id' )->unsigned();
			$table->timestamps();

			$table->index( 'period_id' );
			$table->foreign( 'period_id' )
				  ->references( 'id' )
				  ->on( 'periods' )
				  ->onUpdate( 'cascade' );

			$table->index( 'doctor_id' );
			$table->foreign( 'doctor_id' )
				  ->references( 'id' )
				  ->on( 'doctors' )
				  ->onUpdate( 'cascade' );

			$table->index( 'account_id' );
			$table->foreign( 'account_id' )
				  ->references( 'id' )
				  ->on( 'register_accounts' )
				  ->onUpdate( 'cascade' );

			$table->index( 'user_id' );
			$table->foreign( 'user_id' )
				  ->references( 'id' )
				  ->on( 'users' )
				  ->onDelete( 'cascade' )
				  ->onUpdate( 'cascade' );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'register_records' );
	}

}
