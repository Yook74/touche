import java.util.Scanner;

class Main{
    //adds two numbers
    public static void main(String[] args){
        Scanner reader = new Scanner(System.in);
        int one, two;
        one = reader.nextInt();
		two = reader.nextInt();
		if (one == 0)
		    while(true) {System.out.println(one+two);}
		 else
		    System.out.println(one+two);

    }
}

