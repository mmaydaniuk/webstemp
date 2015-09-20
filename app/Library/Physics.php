<?php
//
//  Physics - helper functions for physics engine
//
class Physics {

	//
	// calculate distance between two points on a grid
	//
	function distance($x1, $y1, $x2, $y) 
	{
		$x = $x2-$x1;
		$y = $y2-$y1;

		return sqrt(($x*$x)+($y*$y));
		// option - approximate (manhattan) distance
		// return abs($x)+abs($y);

	}

	//
	// return the sign of a number -1,0 or 1
	//
	function sgn($n) {
	    return ($n > 0) - ($n < 0);
	}

	//
	// given a current position, a destination, a velocity and a number of cycles
	// calculate the new position
	//
	function calcStraightLinePosition($x1, $y1, $x2, $y2, $velocity, $cycles)
	{
	 

	  $dx=$x2-$x1;      /* the horizontal distance of the line */
	  $dy=$y2-$y1;      /* the vertical distance of the line */
	  
	  $dxabs=abs($dx);
	  $dyabs=abs($dy);
	  $sdx=$this->sgn(dx);
	  $sdy=$this->sgn(dy);

	  $x=$dyabs>>1;
	  $y=$dxabs>>1;

	  $px=$x1;
	  $py=$y1;

	 

	  if ($dxabs>=$dyabs) /* the line is more horizontal than vertical */
	  {
	    for($i=0;$i<$dxabs;$i++)
	    {
	      $y+=$dyabs;
	      if ($y >= $dxabs)
	      {
	        $y-=$dxabs;
	        $py+=$sdy;
	      }
	      $px+=$sdx;
	      //plot_pixel($px,$py);
	    }
	  }
	  else /* the line is more vertical than horizontal */
	  {
	    for($i=0;$i<$dyabs;$i++)
	    {
	      $x+=$dxabs;
	      if ($x>=$dyabs)
	      {
	        $x-=$dyabs;
	        $px+=$sdx;
	      }
	      $py+=$sdy;
	      //plot_pixel(px,py,color);
	    }
	  }
	}

}
?>